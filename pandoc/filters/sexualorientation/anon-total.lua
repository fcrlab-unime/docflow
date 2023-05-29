function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('sexualorientation') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('sexualorientation')
  span.classes:remove(position)
end
return span
end