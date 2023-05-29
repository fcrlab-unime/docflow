function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('genderidentity') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('genderidentity')
  span.classes:remove(position)
end
return span
end