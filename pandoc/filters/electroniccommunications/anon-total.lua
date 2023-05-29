function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('electroniccommunications') then
  span.content = pandoc.Str('xxxxxxxxx')
  local value, position = span.classes:find('electroniccommunications')
  span.classes:remove(position)
end
return span
end